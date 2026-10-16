namespace LIMS_API.Entities.RequestPending
{
    public class GetTable
    {
        public int RequestId { get; set; }
        public string? CustomerName { get; set; }
        public int? DivisionSection { get; set; }
        public DateTime? DateTime { get; set; }
        public string? LabAnalysis { get; set; }
        public DateTime? TargetDate { get; set; }
    }
}
