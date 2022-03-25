using MediatR;
using WordsCountBot.Domain;
using WordsCountBot.Infrastructure.EntityFramework;

namespace WordsCountBot.Application.Command.NewText;

public class NewTextCommandHandler : IRequestHandler<NewTextCommand>
{
    private WordContext _wordContext;

    public NewTextCommandHandler(WordContext wordContext)
    {
        _wordContext = wordContext;
    }

    public async Task<Unit> Handle(NewTextCommand request, CancellationToken cancellationToken)
    {
        var words = request.Text.Split(" ");

        await _wordContext.Words.AddRangeAsync(words.Select(w => new Word
        {
            Text = w,
            ChatId = request.ChatId,
            AuthorId = request.AuthorId,
        }));
        await _wordContext.SaveChangesAsync();

        return Unit.Value;
    }
}