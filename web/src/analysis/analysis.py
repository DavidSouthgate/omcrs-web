#!/usr/bin/python3

# OMP_Analysis.py
# Adapted from the tutorial "Document Clustering With Python" by Brandon Rose
# Written by Chase Condon

# Don't use xwindows for plots
import matplotlib
matplotlib.use('Agg')

import json
import pymysql
import os
import sys
import pandas as pd
import re
import nltk
import math
from nltk.stem.snowball import SnowballStemmer
from sklearn.pipeline import Pipeline
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.cluster import KMeans
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.manifold import MDS
import matplotlib.pyplot as plt, mpld3

def error(code, message):
    output = {
        "error": {
            "code": code,
            "message": message,
        }
    }
    print(json.dumps(output))
    sys.exit()

# Get MySQL database info from environment
db_host = os.environ['MYSQL_HOST']
db_user = os.environ['MYSQL_DATABASE']
db_password = os.environ['MYSQL_PASSWORD']
db_name = os.environ['MYSQL_DATABASE']

# Get session question ID from argument
sessionQuestionID = int(sys.argv[1])

# Open database connection
db = pymysql.connect(db_host, db_user, db_password, db_name)

# If database is not connected, exit script
if not db.open:
    sys.exit()

# Run query to get all responses for this this question
cur_r = db.cursor()
sql = "SELECT ID, userID, response " + \
      "FROM `omcrs_response` AS r " + \
      "WHERE r.`sessionQuestionID` = %s"
cur_r.execute(sql, sessionQuestionID)

# Data structure used to store responses
responses = {'responseID': [], 'userID': [], 'response': []}

# Load responses
for row_r in cur_r:

    # Put row into more sensible variables
    responseID = row_r[0]
    userID = row_r[1]
    response = row_r[2]

    # Add response to data structure
    responses['responseID'].append(responseID)
    responses['userID'].append(userID)
    responses['response'].append(response)

response_frame = pd.DataFrame(responses, columns=['responseID', 'userID', 'response'])
stopwords = nltk.corpus.stopwords.words('english')
stemmer = SnowballStemmer("english")

def tokenize(text):
    tokens = [word.lower() for sent in nltk.sent_tokenize(text) for word in nltk.word_tokenize(sent)]
    filtered_tokens = []
    for token in tokens:
        if re.search('[a-z]', token):
            filtered_tokens.append(token)
    stems = [stemmer.stem(t) for t in filtered_tokens]
    return stems

pipeline = Pipeline([('vect', TfidfVectorizer(max_df=0.8, max_features=20000,
                                              min_df=0.2, stop_words='english',
                                              use_idf=True, tokenizer=tokenize,
                                              ngram_range=(1, 3))),
                     ('clust', KMeans(n_clusters=3))])

try:
    pipeline.fit(response_frame['response'])
except ValueError:
    error("notEnoughClusters", "notEnoughClusters");
    sys.exit()

matrix = pipeline.named_steps['vect'].fit_transform(response_frame['response'])
dist = 1 - cosine_similarity(matrix)

clusters = pipeline.named_steps['clust'].labels_.tolist()

cluster_list = [[], [], []]
for i, cluster in enumerate(clusters):
    cluster_list[cluster] = cluster_list[cluster] + re.findall(r"[\w']+|[.,!?;]", response_frame['response'][i])
punctuation = list(['.', ',', '!', '?', ';'])
for i in range(len(cluster_list)):
    cluster_list[i] = [word.lower() for word in cluster_list[i] if word not in stopwords and word not in punctuation]


def tfidf(word, cluster, cluster_list):
    # return the term frequency multiplied by the inverse document frequency
    # term frequency = number of times the word appears in the cluster normalized by the number of words in the cluster
    # inverse document frequency = the log of the number of clusters normalized by the number of clusters in which the word appears at least once
    return (cluster.count(word) / len(cluster)) * (math.log(len(cluster_list) / (1 + sum(1 for cluster in cluster_list if word in cluster))))

cluster_labels = []
for cluster in cluster_list:
    scores = {word: tfidf(word, cluster, cluster_list) for word in cluster}
    sorted_words = sorted(scores.items(), key=lambda x: x[1], reverse=True)
    label = ""
    for word, score in sorted_words[:2]:
        if label == "":
            label = word
        else:
            label += ", " + word
    cluster_labels.append(label)

mds = MDS(n_components=2, dissimilarity='precomputed', random_state=1)
pos = mds.fit_transform(dist)
x, y, = pos[:, 0], pos[:, 1]

clustered_responses = {'responseID': response_frame['responseID'], 'userID': response_frame['userID'], 'response': response_frame['response'], 'cluster': clusters,
                           'x': x, 'y': y}
cluster_frame = pd.DataFrame(clustered_responses, columns=['responseID', 'userID', 'response', 'cluster', 'x', 'y'])

cluster_colors = {0: '#1b9e77', 1: '#d95f02', 2: '#7570b3'}
cluster_names = {0: 'Cluster 1', 1: 'Cluster 2', 2: 'Cluster 3'}

df = pd.DataFrame(dict(x=x, y=y, label=clusters, title=cluster_frame['userID']))
groups = df.groupby('label')

fig, ax = plt.subplots(figsize=(17, 9))
ax.margins(0.05)

for name, group in groups:
    ax.plot(group.x, group.y, marker='o', linestyle='', ms=12,
             label=cluster_names[name], color=cluster_colors[name],
                mec='none')
    ax.set_aspect('auto')
    ax.tick_params( \
        axis='x',
        which='both',
        bottom=False,
        top=False,
        labelbottom=False)
    ax.tick_params( \
        axis='y',
        which='both',
        left=False,
        top=False,
        labelleft=False)

ax.legend(numpoints=1)

output = [];

# Loop through clusters
for c in cluster_frame.values:

    # Use more sensible variable names for cluster variables
    responseID = c[0]
    response = c[2]
    cluster = c[3]
    x = c[4]
    y = c[5]

    output.append({
        "responseID": responseID,
        "response": response,
        "cluster": cluster,
	"cluster_label": cluster_labels[cluster],
        "x": x,
        "y": y
    });

# Disconnect from database
db.close()

# Output JSON onto only line of output
print(json.dumps(output))
