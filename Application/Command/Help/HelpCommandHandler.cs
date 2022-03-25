using MediatR;
using Telegram.Bot;

namespace WordsCountBot.Application.Command.Help;

public class HelpCommandHandler : IRequestHandler<HelpCommand>
{
    private readonly ITelegramBotClient _client;

    public HelpCommandHandler(ITelegramBotClient client)
    {
        _client = client;
    }

    public async Task<Unit> Handle(HelpCommand request, CancellationToken cancellationToken)
    {
        await _client.SendTextMessageAsync(
            chatId: request.ChatId,
            text: "This bot works in group chats only. First, write some word to the chat. Then, send /count {this word} command to the same chat."
        );

        return Unit.Value;
    }
}