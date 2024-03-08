#!/bin/bash

export COMPOSER=composer-dev.json 

function update()
{
    git pull
    composer update
}

function publish()
{
    git checkout stable
    git merge main
    git push
    git checkout main
}

function develop()
{
   git checkout main
   git pull
}

function status()
{
   git status
}

case $1 in

  update)
    echo "Updating..."
	update	
    ;;

  publish)
   echo "Publishing..."
	publish	
    ;;

  develop)
   echo "Develop..."
	develop	
    ;;

 status)
	status 	
    ;; 
 *)
   echo "Usage:"
   echo "project update|publish|develop|status"
   ;;
esac
