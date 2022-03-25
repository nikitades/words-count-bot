using Microsoft.EntityFrameworkCore;
using WordsCountBot.Domain;
using WordsCountBot.Infrastructure.EntityFramework;

var builder = WebApplication.CreateBuilder(args);

// Add services to the container.

builder.Services.AddControllers();
// Learn more about configuring Swagger/OpenAPI at https://aka.ms/aspnetcore/swashbuckle
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

builder.Services.AddDbContext<WordContext>(options =>
{
    options.UseMySql(builder.Configuration.GetConnectionString("Words"), new MySqlServerVersion(new Version(8, 0, 28)));
}
);

builder.Services.AddScoped<IWordRepository, WordRepository>();

var app = builder.Build();

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
