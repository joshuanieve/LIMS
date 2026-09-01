using System;
using System.Collections.Generic;
using Microsoft.EntityFrameworkCore;

namespace LIMS_API.Models;

public partial class LimsContext : DbContext
{
    public LimsContext()
    {
    }

    public LimsContext(DbContextOptions<LimsContext> options)
        : base(options)
    {
    }

    public virtual DbSet<LabAnalysisList> LabAnalysisLists { get; set; }

    public virtual DbSet<LabRequest> LabRequests { get; set; }

    public virtual DbSet<LabRequestList> LabRequestLists { get; set; }

    protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
#warning To protect potentially sensitive information in your connection string, you should move it out of source code. You can avoid scaffolding the connection string by using the Name= syntax to read it from configuration - see https://go.microsoft.com/fwlink/?linkid=2131148. For more guidance on storing connection strings, see https://go.microsoft.com/fwlink/?LinkId=723263.
        => optionsBuilder.UseSqlServer("Server=10.0.224.92;Database=LIMS;User Id=sa;Password=Srvr_VmNsap2026!;TrustServerCertificate=True;");

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder.Entity<LabAnalysisList>(entity =>
        {
            entity
                .HasNoKey()
                .ToTable("Lab_AnalysisList");

            entity.Property(e => e.AnalysisId)
                .ValueGeneratedOnAdd()
                .HasColumnName("AnalysisID");
            entity.Property(e => e.Analyte).HasMaxLength(255);
            entity.Property(e => e.Category).HasMaxLength(255);
            entity.Property(e => e.Method).HasMaxLength(255);
            entity.Property(e => e.Type).HasMaxLength(255);
        });

        modelBuilder.Entity<LabRequest>(entity =>
        {
            entity
                .HasNoKey()
                .ToTable("Lab_Request");

            entity.HasIndex(e => e.RequestId, "IX_Lab_Request").IsUnique();

            entity.Property(e => e.CustomerName).HasMaxLength(255);
            entity.Property(e => e.DivisionSection).HasMaxLength(255);
            entity.Property(e => e.EmailAddress).HasMaxLength(255);
            entity.Property(e => e.LabAnalysis).HasMaxLength(255);
            entity.Property(e => e.RequestId)
                .ValueGeneratedOnAdd()
                .HasColumnName("RequestID");
            entity.Property(e => e.SampleRetrieval).HasMaxLength(255);
            entity.Property(e => e.SampleType).HasMaxLength(255);
            entity.Property(e => e.SubInfo2).HasMaxLength(255);
            entity.Property(e => e.SubInfo4).HasMaxLength(255);
        });

        modelBuilder.Entity<LabRequestList>(entity =>
        {
            entity
                .HasNoKey()
                .ToTable("Lab_RequestList");

            entity.Property(e => e.Analysis).HasMaxLength(255);
            entity.Property(e => e.CustomerSampleCode).HasMaxLength(255);
            entity.Property(e => e.LaboratoryNumber).HasMaxLength(255);
            entity.Property(e => e.PlaceCollected).HasMaxLength(255);
            entity.Property(e => e.RequestId).HasColumnName("RequestID");
            entity.Property(e => e.Sample).HasMaxLength(255);
            entity.Property(e => e.TestId)
                .ValueGeneratedOnAdd()
                .HasColumnName("TestID");
        });

        OnModelCreatingPartial(modelBuilder);
    }

    partial void OnModelCreatingPartial(ModelBuilder modelBuilder);
}
