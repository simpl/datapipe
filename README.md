Name
====

datapipe

Table of Contents
=================

* [Name](#name)
* [Description](#description)
* [Advantages](#advantages)
* [Original purpose](#original-purpose)
* [Copyright & License](#copyright--license)

Description
===========

Datapipe is a command-line oriented tool to facilitate the process of fetching, manipulating and re-formatting
data. Some of the features include:

- fetching data (HTTP, FTP, email, SSH)
- loading data (CSV, column-aligned, JSON)
- processing data (adding, deleting, merging)
- outputting data (CSV, JSON)

It is written in PHP, with the configurations of the actions defined in YAML for clarity. It's designed to be
used from the command-line, and the scripts can either include all the PHP files or be bundled into one for
ease of porting.

Advantages
==========

- clear configurations written in YAML that are typically just a few hundred lines
- data is piped between commands in an intelligent way (so many variables don't need to be set explicitly)
- an arbitrary number of data sources can be processed in parallel

[Back to TOC](#table-of-contents)

Original purpose
================

It was originally designed for the purpose of fetching product inventories, prices and descriptions from
different sources (FTP, HTTP, email...) and to load / manipulate / merge the data into a standard format to be
loaded into a product inventory management system, but has been designed in a generic way so that it could be
used for arbitrary data.

[Back to TOC](#table-of-contents)

Copyright & License
===================

The bundle itself is licensed under the 2-clause BSD license.

Copyright (c) 2017-2018, Marcus Clyne

This module is licensed under the terms of the BSD license.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are
met:

* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

[Back to TOC](#table-of-contents)
