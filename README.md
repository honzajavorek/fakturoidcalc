
# FakturoidCalc

FakturoidCalc is a little script extending [@fakturoid](https://github.com/fakturoid/). It looks at your invoices via Fakturoid API and tries to calculate all the crap our country (Czech Republic) demands.

## Status: IDLE

This my hobby project is idle since 2011. Until I need it again, there is probably [no future for it](https://www.youtube.com/watch?v=yqrAPOZxgzU). As it's PHP app and I became a Python person, I'd probably just write a new tool from scratch in that case. Feel free to [fork this](https://github.com/honzajavorek/fakturoidcalc/fork).

## Installation

FakturoidCalc is CLI PHP script. That means you have PHP installed with support of CLI (and cURL, which is dependency) and then you just invoke `fakturoidcalc` script from your terminal. It's handy to put it in your system `PATH`. However, you can also invoke it from your browser - just setup a server and point your browser to `index.php`.

## Configuration

FakturoidCalc asks for configuration file. In such file you define your numbers and current conditions published by the institutions.

Put your settings into `data/foobar2012.ini` file, where foobar is whatever you want and 2012 is corresponding year. There already is `example2010.ini` so you know how to make your own. If you are not a student or don't have similarly simple rules to count your taxes, this tool wont't help you. So no solar or wind power plants, sorry!

## License: ISC

© 2011 Jan Javorek &lt;<a
href="mailto:jan.javorek&#64;gmail.com">jan.javorek&#64;gmail.com</a>&gt;

This work is licensed under [ISC license](https://en.wikipedia.org/wiki/ISC_license).
