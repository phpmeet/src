git commit -a -m "upload"
git push origin

curl -XPOST -H'content-type:application/json' 'https://packagist.org/api/bitbucket?username=phpmeet&apiToken=yXrVOTAHoCLSK2OEadGR' -d'{"repository":{"url":"https://gitee.com/phpmeet/src.git"}}'