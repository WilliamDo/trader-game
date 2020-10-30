CONTEXT = ../webapps/ROOT
CLSDIR  = $(CONTEXT)/WEB-INF/classes
JAVAFILES = $(wildcard *.java)
CLASSFILES = $(wildcard *.class)

all: me install

clean:
	/bin/rm -f $(CLASSFILES) */*.class

install:	$(CLASSFILES)
	bash install.sh


me:	$(JAVAFILES)
	javac $(JAVAFILES)
