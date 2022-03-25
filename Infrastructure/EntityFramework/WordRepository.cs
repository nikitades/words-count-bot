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

    public async Task<int> GetUsagesCount(string text)
    {
        return await _wordContext.Words.Where(word => word.Text == text).CountAsync();
    }
}