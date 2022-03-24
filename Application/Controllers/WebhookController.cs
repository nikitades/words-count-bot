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

    [Route("/api/webhook")]
    [HttpPost]
    public string Get([FromBody] Update update)
    {
        _logger.Log(LogLevel.Information, "Hello");
        return "ok";
    }

    [Route("/api/ping")]
    [HttpGet]
    public string Ping()
    {
        _wordRepository.GetUsagesCount("privet");
        _logger.Log(LogLevel.Information, "ping");
        return "pong";
    }
}
