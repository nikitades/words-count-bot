using MediatR;
using Microsoft.EntityFrameworkCore;
using Telegram.Bot;
using Telegram.Bot.Types.Enums;
using WordsCountBot.Infrastructure.EntityFramework;

namespace WordsCountBot.Application.Command.GetCount;

public class GetCountCommandHandler : IRequestHandler<GetCountCommand>
{
    private readonly ITelegramBotClient _client;
    private readonly WordContext _wordContext;

    public GetCountCommandHandler(
        WordContext wordContext,
        ITelegramBotClient client
    )
    {
        _wordContext = wordContext;
        _client = client;
    }

    public async Task<Unit> Handle(GetCountCommand request, CancellationToken cancellationToken)
    {
        var count = await _wordContext.Words.Where(w => w.ChatId == request.ChatId).Where(w => w.Text == request.Word).CountAsync();

        await _client.SendTextMessageAsync(
            chatId: request.ChatId,
            text: $"*{request.Word}*: {count}",
            parseMode: ParseMode.Markdown
        );

        return Unit.Value;
    }
}