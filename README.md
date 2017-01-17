We have small application to subscribe users where we'd like to implement [Command Query Separation]
(http://martinfowler.com/bliki/CommandQuerySeparation.html) to eventually migrate to the [CQRS]
(http://martinfowler.com/bliki/CQRS.html) pattern.

To subscribe a user we call
    
    bin/console user:subscribe tech@ulabox.com

We would like you to finish this console action to subscribe a user and print some info about it using CQS best practices.
We would also like to record some domain events (well, in this case there's only one possible event) even though there are no listeners at this point.
And of course, a little bit of testing (functional, unit) using behat/phpspec or PHPUnit won't hurt either ;)

In the given composer.json you have already included an implementation of the Command Bus pattern
using [Simple Bus - Message Bus](https://github.com/SimpleBus/MessageBus) that you may find convenient. Feel free to use any other Command Bus implementation you like.

 
    


