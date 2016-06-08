# magento-settings-edit
Script to edit settings in magento's generated env.php file

usage: php edit-settings.php infile edits outfile

example: php edit-settings.php env.php "backend.frontName='admin_blah'" env.php

Multiple edits should be delimited by a semicolon. There should be no whitespace around the assignment. (eg blah=5 not blah = 5). String variables should be surrounded by single quotes. Integers should not be quoted.

example: php edit-settings.php env.php "backend.frontName='admin_blah';cache_types.config=1" env.php

