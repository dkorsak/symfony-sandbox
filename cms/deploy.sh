#!/bin/bash

host=@deploy.host@
port=@deploy.port@
user=@deploy.user@
dest=@deploy.dest@

clearfilecache=@deploy.rsync.clear.cache.file@
clearsonata=@deploy.rsync.clear.sonata.cache@
cleardoctrine=@deploy.rsync.clear.doctrine.cache@
assetsdump=@deploy.rsync.assets.dump@
buildbd=@deploy.rsync.build.db@
migrate=@deploy.rsync.doctrine.migrations@
enablemaitenance=@deploy.lock@

if [ "$1" == "run" ] 
then
  echo "Syncing $user@$host:$dest"
  dry_run=""
else  
  echo "Dry run $user@$host:$dest"
  dry_run="--dry-run"
fi

rsync --progress $dry_run -rlzcC --force --delete --exclude-from=./rsync_exclude.txt -e "ssh -p$port" ./ $user@$host:$dest

if [ "$1" == "run" ] 
then

  echo "Cleaning APC cache............."
  ssh $user@$host '@deploy.dest@/app/console apc:clear'

  if $enablemaitenance
  then
    echo "Enabling maitenance page............."
    ssh $user@$host '@deploy.dest@/app/console lexik:maintenance:lock --no-interaction --set-ttl'
  fi

  if $clearfilecache
  then
    echo "Cleaning file cache............."
    ssh $user@$host 'rm @deploy.dest@/app/cache/* -Rf'
    ssh $user@$host '@deploy.dest@/app/console cache:clear --env=prod --no-debug'
  fi

  if $clearsonata
  then
    echo "Cleaning sonata cache............."
    ssh $user@$host '@deploy.dest@/app/console sonata:cache:flush-all --env=prod'
  fi

  if $cleardoctrine
  then
    echo "Cleaning doctrine cache............."
    ssh $user@$host '@deploy.dest@/app/console doctrine:cache:clear-metadata --env=prod'
    ssh $user@$host '@deploy.dest@/app/console doctrine:cache:clear-query --env=prod'
    ssh $user@$host '@deploy.dest@/app/console doctrine:cache:clear-result --env=prod'
  fi

  if $assetsdump
  then
    echo "Installing assets..........."
    ssh $user@$host '@deploy.dest@/app/console assets:install @deploy.dest@/web'
    ssh $user@$host '@deploy.dest@/app/console assetic:dump --env=prod --no-debug'
  fi

  if $buildbd
  then
    echo "Building database..........."
    ssh $user@$host '@deploy.dest@/app/console doctrine:schema:drop --force --env=prod'
    ssh $user@$host '@deploy.dest@/app/console doctrine:schema:create --env=prod'
    ssh $user@$host '@deploy.dest@/app/console app:create-pdo-session-table'
    ssh $user@$host '@deploy.dest@/app/console doctrine:fixtures:load --no-interaction'
  fi

  if $migrate
  then
     echo "Migrating database........."
     ssh $user@$host '@deploy.dest@/app/console doctrine:migrations:migrate --no-interaction'
  fi

  if $enablemaitenance
  then
    echo "Disabling maitenance page............."
    ssh $user@$host '@deploy.dest@/app/console lexik:maintenance:unlock --no-interaction'
  fi
fi


