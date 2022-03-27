using MediatR;
using Telegram.Bot;
using Telegram.Bot.Types.Enums;
using WordsCountBot.Domain;

namespace WordsCountBot.Application.Command.GetCount;

public class GetCountCommandHandler : IRequestHandler<GetCountCommand>
{
    private readonly ITelegramBotClient _client;
    private readonly IWordRepository _wordRepository;

    public GetCountCommandHandler(IWordRepository wordRepository, ITelegramBotClient client)
    {
        _wordRepository = wordRepository;
        _client = client;
    }

    public async Task<Unit> Handle(GetCountCommand request, CancellationToken cancellationToken)
    {
        var count = _wordRepository.GetUsagesCount(request.Word, request.ChatId);

        await _client.SendTextMessageAsync(
            chatId: request.ChatId,
            text: $"*{request.Word}*: {count}",
            parseMode: ParseMode.Markdown
        );

        return Unit.Value;
    }
}
