using LIMS_API.Entities.RequestForm;
using LIMS_API.Models;
using Microsoft.EntityFrameworkCore;

namespace LIMS_API.Repositories.RequestForm
{
    public class RequestFormRepo : IRequestFormRepo
    {
        private readonly LimsContext _context;

        public RequestFormRepo(LimsContext context)
        {
            _context = context;
        }


        public async Task<int> CreateRequestForm(CreateRequestForm request)
        {
            using var transaction =
                await _context.Database.BeginTransactionAsync();

            try
            {
                // INSERT MAIN REQUEST
                var now = DateTime.Now;
                var labRequest = new LabRequest
                {
                    CustomerName = request.CustomerName,
                    EmailAddress = request.EmailAddress,
                    DivisionSection = request.DivisionSection,
                    DateTime = now,
                    TargetDate = request.DateTimeRelease,
                    LabAnalysis = request.LabAna,
                    SampleType = request.SamType != "Others"
                        ? request.SamType
                        : request.SamTypeSpecify,
                    SampleRetrieval = request.SampRet,
                    SubInfo1 = request.Subinfo1,
                    SubInfo3 = request.Subinfo3,

                    // Current database columns are strings
                    SubInfo2 = request.Subinfo2 == true
                        ? request.Subinfo2Specify
                        : null,

                    SubInfo4 = request.Subinfo4 == true
                        ? request.Subinfo4Specify
                        : null,
                    Instruction = request.Instruction,
                    Status = 0,

                    CreatedAt = now,
                    UpdatedAt = now
                };


                _context.LabRequests.Add(labRequest);
                await _context.SaveChangesAsync();

                // SQL generated RequestId is now available
                int requestId = labRequest.RequestId;

                ////////// NEW: LOCK + READ the last lab number
                int year = now.Year;

                ////////// LOCK THE TABLE AND GET THE LAST NUMBER ///////////////
                var result = await _context.Database
                    .SqlQuery<int>($@"
                        SELECT Count AS Value
                        FROM Lab_Counter WITH (UPDLOCK, HOLDLOCK)
                        WHERE [Year] = {year}")
                    .ToListAsync();

                int lastNumber = result.FirstOrDefault();

                // INSERT ALL SAMPLES
                foreach (var sample in request.Samples)
                {
                    lastNumber++;
                    var labRequestList = new LabRequestList
                    {
                        RequestId = requestId,
                        LaboratoryNumber = $"{year}-{lastNumber:D4}",
                        Sample = sample.Sample,
                        CustomerSampleCode = sample.CustomerSampleCode,
                        DateTimeCollected = sample.DateCollected,
                        PlaceCollected = sample.PlaceCollected,
                        Analysis = sample.Testarray,

                        CreatedAt = DateTime.Now,
                        UpdatedAt = DateTime.Now
                    };


                    _context.LabRequestLists.Add(
                        labRequestList
                    );
                }

                await _context.SaveChangesAsync();
                await _context.Database.ExecuteSqlAsync($@"
                    UPDATE Lab_Counter SET Count = {lastNumber} WHERE [Year] = {year};
                    IF @@ROWCOUNT = 0
                        INSERT INTO Lab_Counter ([Year], Count) VALUES ({year}, {lastNumber});");

                await transaction.CommitAsync();
                return requestId;
            }
            catch
            {
                await transaction.RollbackAsync();
                throw;
            }
        }
    }
}