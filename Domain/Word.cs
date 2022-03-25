using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using Microsoft.Extensions.WebEncoders.Testing;

namespace WordsCountBot.Domain;

public class Word
{
    public int Id { get; init; }
    public string Text { get; init; } = "";
    public long AuthorId { get; init; }
    public long ChatId { get; init; }
}