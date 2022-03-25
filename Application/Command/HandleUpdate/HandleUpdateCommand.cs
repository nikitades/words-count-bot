using MediatR;
using Telegram.Bot.Types;

namespace WordsCountBot.Application.Command.HandleUpdate;

public record HandleUpdateCommand(Update Update) : IRequest;