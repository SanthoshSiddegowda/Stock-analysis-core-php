# Stock Market Analyser - Core PHP

Stock market analyser is the web tool which collects the data from csv uploaded. Analysis and calculate the given data and display results.

## Prerequisites

If you don't already have an Apache local environment with PHP, use one of the following links:

 - Windows: https://updivision.com/blog/post/beginner-s-guide-to-setting-up-your-local-development-environment-on-windows
 - Linux: https://howtoubuntu.org/how-to-install-lamp-on-ubuntu
 - Mac: https://wpshout.com/quick-guides/how-to-install-mamp-on-your-mac/

## File Structure

```
+-- controllers
|   +-- ResultController.php
|
+-- resources
|   +-- assets
|       +-- Scripts.js
|   +-- constants
|       +-- footer.php
|       +-- header.php
|   +-- pages
|       +-- home.php
|       +-- result.php
|
+-- storage
|   +-- csv
|
+-- .gitignore
+-- index.php
+-- readme.md
+-- result.php
+-- style.css
```

## Features

1. User Freindly UI.
2. Accept Unsorted CSV and Unfiltered Csv.
3. Accept multiple Date Formats from the CSV.
4. Stock Name is Auto picked from the CSV.
5. Object-Oriented Approach in coding.
6. Provides information on best date to buy and sell shares.

## Note

For Reference, A dummy CSV file added in the path ``` storage/csv``` . Need to follow Headers of csv with same names in order to use the tool.  

## Reporting Issues

For Issues, you can raise the issue on the repo in the github project. [issue](https://github.com/SanthoshSiddegowda/Stock-Analsys-core-php/issues)

## License

MIT
