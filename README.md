Datapipe
========


# Description

Datapipe is a command-line oriented tool to facilitate the process of fetching, manipulating and re-formatting
data. Some of the features include:

- fetching data (HTTP, FTP, email, SSH)
- loading data (CSV, column-aligned, JSON)
- processing data (adding, deleting, merging)
- outputting data (CSV, JSON)

It is written in PHP, with the configurations of the actions defined in YAML for clarity. It's designed to be
used from the command-line, and the scripts can either include all the PHP files or be bundled into one for
ease of porting.


# Advantages

- clear configurations written in YAML that are typically just a few hundred lines
- data is piped between commands in an intelligent way (so many variables don't need to be set explicitly)
- an arbitrary number of data sources can be processed in parallel



# Original purpose

It was originally designed for the purpose of fetching product inventories, prices and descriptions from
different sources (FTP, HTTP, email...) and to load / manipulate / merge the data into a standard format to be
loaded into a product inventory management system, but has been designed in a generic way so that it could be
used for arbitrary data.