cd /code/ttt/

git init > /dev/null
git remote add origin https://github.com/KIMB-technologies/TaskTimeTerminate.git > /dev/null

git fetch --tags --quiet
latestTag=$(git describe --tags `git rev-list --tags --max-count=1`)
git checkout "$latestTag" --quiet

chmod +x ./cli.php ./record.php ./install.sh

# change stty
rm /bin/stty
echo '#!/bin/sh' > /bin/stty
echo 'if [ $1 = "size" ]; then' >> /bin/stty
echo '	echo "50 25";' >> /bin/stty
echo 'else' >> /bin/stty
echo '	/bin/busybox stty "$@"' >> /bin/stty
echo 'fi;' >> /bin/stty
chmod +x /bin/stty