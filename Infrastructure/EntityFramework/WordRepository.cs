using Microsoft.EntityFrameworkCore;
using WordsCountBot.Domain;

namespace WordsCountBot.Infrastructure.EntityFramework;

public class WordRepository : IWordRepository
{
    private readonly WordContext _wordContext;

    public WordRepository(WordContext wordContext)
    {
        _wordContext = wordContext;
    }

    public async Task Add(IEnumerable<Word> words)
    {
        await _wordContext.Words.AddRangeAsync(words);
        await _wordContext.SaveChangesAsync();
    }

    public async Task<int> GetUsagesCount(string text, long chatId)
    {
        return await _wordContext.Words
            .Where(w => w.ChatId == chatId)
            .Where(w => w.Text == text)
            .CountAsync();
    }
}
