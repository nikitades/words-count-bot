# A telegram bot to count words in your chat

### The goal

I stated this project to get used to Symfony framework. The idea is rather simple - to create a bot telling how many times a word was used in certain chat.

- - -

### How to use the bot

1. Find @WordsCountBot in Telegram
2. Add it to the chat you want to count the words in
3. Type something (more than 3 characters)
4. Send `/count` command
5. Also you can send `/count <word>` command to get the number of times this particular word was used

- - -

### Libraries used

- longman/telegram-bot - a nice and friendly telegram bot project. Takes 1 minute to start using
- PHPUnit - requires no introduction. Wanted ensure that the commands are stable
