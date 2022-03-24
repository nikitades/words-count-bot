namespace WordsCountBot.Domain;

public interface IWordRepository
{
    Task<int> GetUsagesCount(string text);
}