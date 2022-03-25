using MediatR;

namespace WordsCountBot.Application.Command.Help;

public record HelpCommand(long ChatId) : IRequest;