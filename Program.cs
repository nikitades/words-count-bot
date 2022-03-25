using System.Reflection;
using MediatR;
using Microsoft.EntityFrameworkCore;
using Telegram.Bot;
using WordsCountBot.Domain;
using WordsCountBot.Infrastructure.EntityFramework;

var builder = WebApplication.CreateBuilder(args);

// Add services to the container.

builder.Services.AddControllers().AddNewtonsoftJson();
// Learn more about configuring Swagger/OpenAPI at https://aka.ms/aspnetcore/swashbuckle
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

builder.Services.AddDbContext<WordContext>(options =>
{
    options.UseMySql(builder.Configuration.GetConnectionString("Words"), new MySqlServerVersion(new Version(8, 0, 28)));
}
);

builder.Services.AddScoped<IWordRepository, WordRepository>();
builder.Services.AddMediatR(Assembly.GetExecutingAssembly());

builder.Services
    .AddHttpClient("tgwebhook")
    .AddTypedClient<ITelegramBotClient>(
        httpClient => new TelegramBotClient(builder.Configuration["BotConfiguration:BotToken"])
    );

var app = builder.Build();

app.Services.GetRequiredService<WordContext>().Database.Migrate();

// Configure the HTTP request pipeline.
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}

app.UseHttpsRedirection();

app.UseAuthorization();

app.MapControllers();

app.Run();
