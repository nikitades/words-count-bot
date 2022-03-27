using Microsoft.EntityFrameworkCore;
using WordsCountBot.Domain;

namespace WordsCountBot.Infrastructure.EntityFramework;

public class WordContext : DbContext
{
    public DbSet<Word> Words { get; set; } = default!;

    public WordContext(DbContextOptions options) : base(options)
    {
    }

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder.Entity<Word>()
            .HasKey(w => w.Id);

        modelBuilder.Entity<Word>()
            .Property(w => w.Id)
            .UseMySqlIdentityColumn()
            .ValueGeneratedOnAdd();
    }
}