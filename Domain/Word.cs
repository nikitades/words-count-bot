using Microsoft.Extensions.WebEncoders.Testing;

namespace WordsCountBot.Domain;

public class Word
{
    public string Text { get; init; }
    public long AuthorId { get; init; }
    public long ChatId { get; init; }

    public Word(string _text, long _authorId, long _chatId)
    {
        Text = _text;
        AuthorId = _authorId;
        ChatId = _chatId;
    }
}