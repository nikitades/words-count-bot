using Microsoft.EntityFrameworkCore;
using WordsCountBot.Domain;

namespace WordsCountBot.Infrastructure.EntityFramework;

public class WordContext : DbContext
{
    public WordContext(DbContextOptions options) : base(options)
    {
    }

    public DbSet<Word> Words { get; set; }
}