<?php

$s="facebook:<a href=\"https://www.facebook.com/events/324877498135730/\">https://www.facebook.com/events/324877498135730/</a><br>ticket:<a href=\"https://tixa.hu/ripoff_durer\">https://tixa.hu/ripoff_durer</a><br>flyer:https://scontent-vie1-1.xx.fbcdn.net/v/t1.0-9/66838413_2298128263638837_2320756969616441344_n.jpg?_nc_cat=101&amp;_nc_oc=AQnOaWxzIr1DUd1W9kHz3FTY3fEzilp-PYKJz1rE9U4Ihy7eCNbzntaWz4gi0tm_uco&amp;_nc_ht=scontent-vie1-1.xx&amp;oh=14521fa6d98f425fd004d1e6e367f395&amp;oe=5E0E9A7F-facebook";


$s=str_replace("<br>", "\n", strip_tags ($s,"<br>"));

echo($s);

?>
