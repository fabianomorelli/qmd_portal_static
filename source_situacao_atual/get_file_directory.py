import os, sys, urllib

if sys.version_info[0] == 2:
	from ConfigParser import SafeConfigParser
#    import ConfigParser as configparser
else:
#    import configparser
	from configparser import SafeConfigParser
#from ConfigParser import SafeConfigParser


def returnPath():
	config = SafeConfigParser()
	config_file = os.path.join(os.path.dirname(__file__),"../config.ini")
	config.read(config_file)
	return config.get("DATA","dir_files")

def getDatabase():
	config = SafeConfigParser()
	config_file = os.path.join(os.path.dirname(__file__),"../config.ini")
	config.read(config_file)
	current = config.get("DATABASE","current")
	database = {
		"host": config.get(current,"host"),
		"user": config.get(current,"user"),
		"password": urllib.parse.quote_plus(config.get(current,"password")),
		"port": config.get(current,"port")
	}
	return database

