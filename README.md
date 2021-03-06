[![Build Status](https://travis-ci.org/marciioluucas/phiber.svg?branch=master)](https://travis-ci.org/marciioluucas/phiber)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/db9f41d9b8144d27ab90a0350cb25a28)](https://www.codacy.com/app/marciioluucas/phiber?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=marciioluucas/phiber&amp;utm_campaign=Badge_Grade)
[![GitHub issues](https://img.shields.io/github/issues/marciioluucas/phiber.svg)](https://github.com/marciioluucas/phiber/issues)
[![GitHub forks](https://img.shields.io/github/forks/marciioluucas/phiber.svg)](https://github.com/marciioluucas/phiber/network)
[![GitHub stars](https://img.shields.io/github/stars/marciioluucas/phiber.svg)](https://github.com/marciioluucas/phiber/stargazers)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/marciioluucas/phiber/master/license)
[![Twitter](https://img.shields.io/twitter/url/https/github.com/marciioluucas/phiber.svg?style=social)](https://twitter.com/intent/tweet?text=Wow:&url=%5Bobject%20Object%5D)

# Phiber - ALPHA version 0.1

[![Imgur](http://i.imgur.com/Ad02NS2.png)](https://nodesource.com/products/nsolid)

Phiber is a simples ORM framework that helps you code your applications more fast.

  - MySQL integration
  - Object Orientation
  - Without SQL

# New Features!

  - Persistence
  - Build websites, apps and api without a line of SQL.
  
  


You can also:
  - See generated SQL
  - Activate console logs.
  
This library has been made by a student of Technology in Systems to Internet of Morrinhos - GO, Brazil.

[![Twitter](https://img.shields.io/badge/IF-Goiano-brightgreen.svg)](https://www.ifgoiano.edu.br/morrinhos)

Criador [Márcio Lucas]

> I made this library to increase my knowledge and to help my friends programmers build apps in PHP more fast, cause I see an big difficulty to build SQLs and i think it is very boring. 

This library are in alpha test, I don't recommend to production environments.

### Technology

Phiber uses just pure PHP and until now only has been builded the part to MySQL 5.5+.
*In the next versions we will implement in BDs like PostgreSQL and Oracle.


### Dependencies

Phiber have depdencies with just Composer.


### Installation

Phiber requires  PHP 5.3.3+ to run and MySQL 5.5+.

Installing Phiber in your project.

You can install by two ways. First is making the Download from github repository and putting the folder of your project
The second way is installing via composer with this command bellow:
```sh
$ composer install marciioluucas/phiber
```

### Config

To config Phiber is very simple,
inside the folder phiber or vendor/phiber gonna have an archive .json called phiber_config.
You are therefore the credentials of the bank and other settings.

phiber/phiber_config.json
```json
{
  "phiber": {
    "language": "pt_br", 
    "link": {
      "database_technology": "mysql", 
      "database_name": "phiber_test", 
      "url": "mysql:host=localhost;dbname=teste_phiber", 
      "user": "root", 
      "password": "", 
      "connection_cache": true 
    },
    "log": true, 
    "execute_queries": true, 
    "code_sync": false 
  }
}
```

### Examples
Now we will create an crud class using Phiber

Notice this class, this is where magic happens.
model/User.php
```php

namespace test;
require 'Phiber.php';
use bin\Restrictions;
use Exception;
use phiber\Phiber;

class User
{

    private $id;
    private $name;
    private $email;
    private $password;

    function get($prop)
    {
        return $this->$prop;
    }

    function set($prop, $value)
    {
        $this->$prop = $value;
    }

    public function validadeInfos()
    {
        if ($this->name != null && $this->name != "") {
            if ($this->email != null && $this->email != "") {
                if ($this->password != null && $this->password != "") {
                    return true;
                }
            }
        }
        return false;
    }

    public function create()
    {
        try {
            if ($this->validadeInfos()) {
                if (Phiber::openPersist()->create($this)) {
                    return json_encode(
                        [
                            "message" => "Success!!!",
                            "type_message" => "INFO"
                        ]
                    );
                } else {
                    return json_encode(
                        [
                            "message" => "Someting wrong happened",
                            "type_message" => "ERROR"
                        ]
                    );
                }
            } else {
                return json_encode(
                    [
                        "message" => "Invalid past values",
                        "type_message" => "ERROR"
                    ]
                );
            }

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function retrave()
    {
        $criteria = Phiber::openPersist();
        $restrictionName = "";
        $restrictionEmail = "";

        /* Here I created a retriction for each attribute */
        if ($this->name != null && $this->name != "") {
            $restrictionName = Restrictions::like("name", $this->name);
            $criteria->add($restrictionName);

        } else if ($this->email != null && $this->email != "") {
            $restrictionEmail = Restrictions::like("email", $this->email);
            $criteria->add($restrictionEmail);

        }
        /* And here I created a conjunction "AND" with the two restrictions*/
        $criteria->add(Restrictions::and($restrictionEmail,$restrictionName));
        return json_encode($criteria->select($this));
    }

    public function update()
    {
        try {
            $criteria = Phiber::openPersist();


            $criteria->add(Restrictions::eq("id", $this->id));
            /*  Here I added a condition "equal" in WHERE of the query.
                The responsible class that creates the query, will return something like this.
                Select from user where id = :condition_id;
                After that, it will be done the binding of values and will be substituted
                the ":condition_id" by your value.
                Read the API doc book to know more about restrictions.
            */

            if ($this->validadeInfos()) {
                if ($criteria->update($this)) {
                    return json_encode(
                        [
                            "message" => "Success!!!",
                            "type_message" => "INFO"
                        ]
                    );
                } else {
                    return json_encode(
                        [
                            "message" => "Someting wrong happened",
                            "type_message" => "ERROR"
                        ]
                    );
                }
            } else {
                return json_encode(
                    [
                        "message" => "Invalid past values",
                        "type_message" => "ERROR"
                    ]
                );
            }

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function delete()
    {
        $criteria = Phiber::openPersist();


        $criteria->add(Restrictions::eq("id", $this->id));
        /*  Here I added a condition "equal" in WHERE of the query.
            The responsible class that creates the query, will return something like this.
            Select from user where id = :condition_id;
            After that, it will be done the binding of values and will be substituted
            the ":condition_id" by your value.
            Read the API doc book to know more about restrictions.
        */

        if ($criteria->delete($this)) {
            return json_encode(
                [
                    "message" => "Success!!!",
                    "type_message" => "INFO"
                ]
            );
        } else {
            return json_encode(
                [
                    "message" => "Someting wrong happened",
                    "type_message" => "ERROR"
                ]
            );
        }
    }

    
}

```

### TODOS:

 - Split classes correctly
 - Do Relationables tables

License
----

MIT


**Free Software, Hell Yeah!**
