using Microsoft.AspNetCore.Mvc;
using Telegram.Bot.Types;
using WordsCountBot.Domain;

namespace WordsCountBot.Application.Controllers;

[ApiController]
[Route("[controller]")]
public class WebhookController : ControllerBase
{
    private readonly ILogger<WebhookController> _logger;
    private readonly IWordRepository _wordRepository;

    public WebhookController(
        ILogger<WebhookController> logger,
        IWordRepository wordRepository
    )
    {
        _logger = logger;
        _wordRepository = wordRepository;
    }

    [HttpPost("/api/webhook")]
    public string Get([FromBody] Update update)
    {
        _logger.Log(LogLevel.Information, "Hello");
        return "ok";
    }

    [HttpGet("/api/ping")]
    public async Task<string> PingAsync()
    {
        var i = await _wordRepository.GetUsagesCount("merhaba");
        _logger.Log(LogLevel.Information, $"ping {i}");
        return "pong";
    }
}
