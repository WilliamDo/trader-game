for FILE in *.class
	do
		install -m600 $FILE ../webapps/ROOT/WEB-INF/classes
done