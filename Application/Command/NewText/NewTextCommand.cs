using MediatR;

namespace WordsCountBot.Application.Command.NewText;

public record NewTextCommand(string Text, long ChatId, long AuthorId) : IRequest;