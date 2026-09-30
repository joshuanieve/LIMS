using LIMS_API.Models;
using LIMS_API.Repositories.AnalysisList;
using LIMS_API.Services.AnalysisList;
using LIMS_API.Repositories.RequestForm;
using LIMS_API.Services.RequestForm;
using LIMS_API.Repositories.RequestPending;
using LIMS_API.Services.RequestPending;
using LIMS_API.Repositories.JobRouting;
using LIMS_API.Services.JobRouting;

using Microsoft.EntityFrameworkCore;
using System.Text.Json;

var builder = WebApplication.CreateBuilder(args);

// Controllers + camelCase JSON
builder.Services.AddControllers()
    .AddJsonOptions(options =>
    {
        options.JsonSerializerOptions.PropertyNamingPolicy =
            JsonNamingPolicy.CamelCase;
    });

// Swagger
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

// DbContext
builder.Services.AddDbContext<LimsContext>(options =>
    options.UseSqlServer(
        builder.Configuration.GetConnectionString("DefaultConnection")
    )
);


// Repository
builder.Services.AddScoped<IAnalysisListRepo, AnalysisListRepo>();
builder.Services.AddScoped<IRequestFormRepo, RequestFormRepo>();
builder.Services.AddScoped<IRequestPendingRepo, RequestPendingRepo>();
builder.Services.AddScoped<IJobRoutingRepo, JobRoutingRepo>();

// Service
builder.Services.AddScoped<IAnalysisListService, AnalysisListService>();
builder.Services.AddScoped<IRequestFormService, RequestFormService>();
builder.Services.AddScoped<IRequestPendingService, RequestPendingService>();
builder.Services.AddScoped<IJobRoutingService, JobRoutingService>();

var app = builder.Build();

// Swagger UI
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}

//app.UseHttpsRedirection();

app.UseAuthorization();

app.MapControllers();

app.Run();