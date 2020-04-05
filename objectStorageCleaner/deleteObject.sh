#!/bin/bash

SECONDS=0

input="logs/objects.log"

counter=0

while IFS= read -r line
do

  aws s3api delete-object --bucket {Bucket Name} --endpoint-url= {Endpoint URL} --key "$line"

  echo "$line deleted"

  counter=$((counter+1))

done < "$input"

echo
echo -e "\e[5m   DONE!!!\e[0m"

if (( $SECONDS > 3600 )) ; then
    let "hours=SECONDS/3600"
    let "minutes=(SECONDS%3600)/60"
    let "seconds=(SECONDS%3600)%60"
    echo "Completed in $hours hour(s), $minutes minute(s) and $seconds second(s)" 
elif (( $SECONDS > 60 )) ; then
    let "minutes=(SECONDS%3600)/60"
    let "seconds=(SECONDS%3600)%60"
    echo "Completed in $minutes minute(s) and $seconds second(s)"
else
    echo "Completed in $SECONDS seconds"
fi

echo -e "Number of items deleted: $counter     "
echo
