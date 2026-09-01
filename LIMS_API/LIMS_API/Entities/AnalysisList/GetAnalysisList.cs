namespace LIMS_API.Entities.AnalysisList
{
    public class GetAnalysisList
    {
        public int AnalysisId { get; set; }

        public string? Type { get; set; }

        public string? Category { get; set; }

        public string? Analyte { get; set; }

        public string? Method { get; set; }
    }
}
