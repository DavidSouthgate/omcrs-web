# OMCRS
OMCRS (One More Class Response System) is a classroom interaction system that allows students to use their own
devices to respond to questions during class.

This version of the software was developed as part of a University of Glasgow Computing Science Level 3 Team Project.

The team consisted of: Nor Albagdadi, Chase Condon, Hristo Ivanov, Michael McGinley and David Southgate

## Requirements
* [Docker](https://www.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/)

# How To Run
To run in a development environment execute the following command (maybe as root):
```
docker-compose -f docker-compose.yml -f docker-compose.dev.yml up
```

* OMCRS: 127.0.0.1:4000
* PhpMyAdmin: 127.0.0.1:4001
* MySql 3306: 127.0.0.1:4002

## Copyright and Licence
OMCRS is released under the [MIT licence](LICENSE.md)