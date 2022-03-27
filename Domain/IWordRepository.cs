namespace WordsCountBot.Domain;

public interface IWordRepository
{
    Task<int> GetUsagesCount(string text, long chatId);

    Task Add(IEnumerable<Word> words);
}