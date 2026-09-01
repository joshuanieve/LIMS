using LIMS_API.Entities.AnalysisList;
using LIMS_API.Models;
using Microsoft.EntityFrameworkCore;

namespace LIMS_API.Repositories.AnalysisList
{
    public class AnalysisListRepo : IAnalysisListRepo
    {
        private readonly LimsContext _context;

        public AnalysisListRepo(LimsContext context)
        {
            _context = context;
        }

        public async Task<List<GetAnalysisList>> GetAnalysisList()
        {
            return await _context.LabAnalysisLists
                .AsNoTracking()
                .Select(x => new GetAnalysisList
                {
                    AnalysisId = x.AnalysisId,
                    Type = x.Type,
                    Category = x.Category,
                    Analyte = x.Analyte,
                    Method = x.Method
                })
                .ToListAsync();
        }
    }
}