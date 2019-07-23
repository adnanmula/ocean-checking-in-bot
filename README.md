# Ocean checking-in bot
Tired of receiving HR mails because you always forget (or you are too lazy) to check-in?
Then it's your lucky day, just plug in your raspberry pi or whatever and forget that you are subdued to this archaic presence control system.

## Not working days
A sqlite db is used to keep track of non working days, add them using yor favorite client or with the following command:

```bash
ocib:not-working-days:add 2019-07-01,2019-07-02
```

## Checking-in
--random (or -r) is optional and defines if there should be a random delay(in seconds) in the checking-in, 10 min is the default amount, which can be overwritten.

```bash
# check-in example in crontab
0 8 * * 1-5 docker-compose run php php bin/console ocib:checkin:add -r 600
```
