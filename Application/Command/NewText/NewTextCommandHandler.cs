using MediatR;
using WordsCountBot.Domain;

namespace WordsCountBot.Application.Command.NewText;

public class NewTextCommandHandler : IRequestHandler<NewTextCommand>
{
    private IWordRepository _wordRepository;

    public NewTextCommandHandler(IWordRepository wordRepository)
    {
        _wordRepository = wordRepository;
    }

    public async Task<Unit> Handle(NewTextCommand request, CancellationToken cancellationToken)
    {
        var words = request.Text.Split(" ");

        await _wordRepository.Add(words.Select(w => new Word
        {
            Text = w,
            ChatId = request.ChatId,
            AuthorId = request.AuthorId,
        }));

        return Unit.Value;
    }
}