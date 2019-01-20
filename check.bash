#!/bin/bash
cd yo;
while :
do
#if [ -f ./*.bash ]
shopt -s nullglob;
# then
	for script in ./*.bash; do "$script" ; done
#fi
#fi
done
