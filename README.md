# wdb
A database for storing information about Wi-Fi networks you have access to: names, locations, and of course keys.

wdb is for storing your WiFi credentials in a standard format that is easy to access by humans and machines. It is
designed to support many more input and output methods than just the HTML interface. It already supports importing from
several common types of config files.

wdb's main uses are easily backing up and restoring WiFi credentials, or moving them from one device to another.

## Features
wdb is simple, and has only one main feature: a list of Wi-Fi networks containing the SSIDs, keys, and location
information. Currently it only supports networks with a pre-shared key.

Additionally, wdb has support for multiple users, and several actions are available via a REST API. It is my intention
to add API access for everything that can be done through the HTML interface.

## Progress
wdb is currently a work-in-progress and should not be considered production-ready. This repository is being used as a
place for me to store my work as I go, and some revisions may not be secure or even functional. 
