using MediatR;
using Telegram.Bot.Types.Enums;
using WordsCountBot.Application.Command.GetCount;
using WordsCountBot.Application.Command.Help;
using WordsCountBot.Application.Command.NewText;

namespace WordsCountBot.Application.Command.HandleUpdate;

public class HandleUpdateCommandHandler : IRequestHandler<HandleUpdateCommand>
{
    private readonly IConfiguration _configuration;
    private readonly IMediator _mediator;

    public HandleUpdateCommandHandler(IMediator mediator, IConfiguration configuration)
    {
        _mediator = mediator;
        _configuration = configuration;
    }

    public async Task<Unit> Handle(HandleUpdateCommand request, CancellationToken cancellationToken)
    {
        if (UpdateType.Message != request.Update.Type)
        {
            return Unit.Value;
        }

        if (MessageType.Text != (request.Update.Message?.Type ?? MessageType.Unknown))
        {
            return Unit.Value;
        }

        if (request.Update.Message?.Text?[0] == '/')
        {
            if (!shouldReactToCommand(request.Update.Message.Text))
            {
                return Unit.Value;
            }

            var action = getAction(request.Update.Message.Text);

            switch (getAction(request.Update.Message.Text))
            {
                case "/count":
                    var word = request.Update.Message?.Text.Split(" ").ElementAtOrDefault(1) ?? "";

                    if ("" == word)
                    {
                        return Unit.Value;
                    }

                    await _mediator.Send(new GetCountCommand(word, request.Update.Message?.Chat.Id ?? 0));
                    break;
                case "/help":
                    await _mediator.Send(new HelpCommand(request.Update.Message.Chat.Id));
                    break;
            }

            return Unit.Value;
        }

        await _mediator.Send(new NewTextCommand(
            Text: request.Update.Message?.Text ?? "",
            ChatId: request.Update.Message?.Chat.Id ?? 0,
            AuthorId: request.Update.Message?.From?.Id ?? 0
        ));
        return Unit.Value;
    }

    private string getAction(string sourceAction)
    {
        var actionWithoutContext = sourceAction.Split(' ')[0];
        var actionWithoutAlias = actionWithoutContext.Split('@')[0];

        return actionWithoutAlias;
    }

    private bool shouldReactToCommand(string sourceAction)
    {
        var alias = sourceAction.Split('@').ElementAtOrDefault(1);

        if (null == alias)
        {
            return true;
        }

        return _configuration["BotConfiguration:BotName"] == alias;
    }
}