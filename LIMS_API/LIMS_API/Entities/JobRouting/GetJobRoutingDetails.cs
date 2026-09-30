namespace LIMS_API.Entities.JobRouting
{
    public class GetJobRoutingDetails
    {
        public int RequestId { get; set; }
        public string? CustomerName { get; set; }
        public string? EmailAddress { get; set; }
        public int? DivisionSection { get; set; }
        public DateTime? DateTime { get; set; }
        public string? LabAnalysis { get; set; }
        public DateTime? DateTimeRelease { get; set; }
        public int? Status { get; set; }
        public List<GetJobRoutingLists> Samples { get; set; } = [];
    }
}
