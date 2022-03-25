using MediatR;

namespace WordsCountBot.Application.Command.GetCount;

public record GetCountCommand(string Word, long ChatId) : IRequest;