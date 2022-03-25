using MediatR;
using Microsoft.AspNetCore.Mvc;
using Telegram.Bot.Types;
using WordsCountBot.Application.Command.HandleUpdate;

namespace WordsCountBot.Application.Controllers;

[ApiController]
[Route("[controller]")]
public class WebhookController : ControllerBase
{
    private readonly IMediator _mediator;

    public WebhookController(IMediator mediator)
    {
        _mediator = mediator;
    }

    [HttpPost("/api/webhook")]
    public async Task<IActionResult> Get([FromBody] Update update)
    {
        await _mediator.Send(new HandleUpdateCommand(update));
        return Ok("ok");
    }

    [HttpGet("/api/ping")]
    public IActionResult PingAsync()
    {
        return Ok("pong");
    }
}
