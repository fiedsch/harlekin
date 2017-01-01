#!/bin/bash

SITE=/Users/andreas/Sites/harlekin

EXTENSION=harlekin

cp -r ./system/modules/$EXTENSION $SITE/system/modules
sudo chown -R _www:staff $SITE
sudo chmod -R  ug+rw $SITE