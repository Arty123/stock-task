Symfony 2.8 console application
=======================

Itransition task
========================

install
```sh
git clone
composer install 
php app/console doc:sch:update --force
php app/console stock:task --test=1
php app/console stock:task
```

There were a few troubles, when I analyzed CSV file:
- Some items don't have stock value (among them P0007). It means that stock level contains an empty string.
- The price of some items contains symbol '$' (P0015).
- Some items don't have an enough fields.

I've got no information, how I should to fix these bugs and I decide just not to insert them into DB.

But also there were few elements with wrong charset (P0001, P0016). All of symbols in the CSV have ASCII charset, but names of P0001 and P0016 contain 
UTF-8 characters. And so I converted them into ASCII and got '?' symbol, then I inserted them into DB.

A added unique index on productCode field in database, but my script always search existing item in database by productCode and get it for updating. If it doesn't exist, 
my script creates new Product and insert it into DB.

And one question:
I noticed that cost in the CSV in GBP, but in task before price values stands symbol "$".
Is it mistake or I need to convert currency?

I didn't create a new bundle, because it is not necessary. Used automaticly generated AppBundle.  
 
       