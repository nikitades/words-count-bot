using Microsoft.Extensions.WebEncoders.Testing;

namespace WordsCountBot.Domain;

public class Word
{
    public string Id { get; init; }
    public string Text { get; init; }
    public long AuthorId { get; init; }
    public long ChatId { get; init; }
}