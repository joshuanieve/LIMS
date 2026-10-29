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

    public virtual DbSet<LabApproval> LabApprovals { get; set; }

    public virtual DbSet<LabCounter> LabCounters { get; set; }

    public virtual DbSet<LabRequest> LabRequests { get; set; }

    public virtual DbSet<LabRequestLabWork> LabRequestLabWorks { get; set; }

    public virtual DbSet<LabRequestList> LabRequestLists { get; set; }

    public virtual DbSet<SysAuditLog> SysAuditLogs { get; set; }

    public virtual DbSet<SysAuditLogsChange> SysAuditLogsChanges { get; set; }

    protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
#warning To protect potentially sensitive information in your connection string, you should move it out of source code. You can avoid scaffolding the connection string by using the Name= syntax to read it from configuration - see https://go.microsoft.com/fwlink/?linkid=2131148. For more guidance on storing connection strings, see https://go.microsoft.com/fwlink/?LinkId=723263.
        => optionsBuilder.UseSqlServer("Server=10.0.224.92;Database=LIMS;User Id=sa;Password=Srvr_VmNsap2026!;TrustServerCertificate=True;");

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder.Entity<LabAnalysisList>(entity =>
        {
            entity.HasKey(e => e.AnalysisId);

            entity.ToTable("Lab_AnalysisList");

            entity.Property(e => e.AnalysisId).HasColumnName("AnalysisID");
            entity.Property(e => e.Analyte).HasMaxLength(255);
            entity.Property(e => e.Category).HasMaxLength(255);
            entity.Property(e => e.Method).HasMaxLength(255);
            entity.Property(e => e.Type).HasMaxLength(255);
        });

        modelBuilder.Entity<LabApproval>(entity =>
        {
            entity.HasKey(e => e.ApprovalId);

            entity.ToTable("Lab_Approval");

            entity.Property(e => e.ApprovalId).HasColumnName("ApprovalID");
            entity.Property(e => e.RequestId).HasColumnName("RequestID");
            entity.Property(e => e.TestId).HasColumnName("TestID");
            entity.Property(e => e.Type)
                .HasMaxLength(255)
                .IsUnicode(false);
            entity.Property(e => e.UserId).HasColumnName("UserID");
        });

        modelBuilder.Entity<LabCounter>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK_LabCounter");

            entity.ToTable("Lab_Counter");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.Count).HasColumnName("count");
            entity.Property(e => e.Year).HasColumnName("year");
        });

        modelBuilder.Entity<LabRequest>(entity =>
        {
            entity.HasKey(e => e.RequestId);

            entity.ToTable("Lab_Request");

            entity.HasIndex(e => e.CustomerId, "Index_CustomerID");

            entity.HasIndex(e => e.ReceivedAt, "Index_ReceivedAt");

            entity.HasIndex(e => e.Status, "Index_Status");

            entity.Property(e => e.RequestId).HasColumnName("RequestID");
            entity.Property(e => e.CustomerId).HasColumnName("CustomerID");
            entity.Property(e => e.CustomerName).HasMaxLength(255);
            entity.Property(e => e.EmailAddress).HasMaxLength(255);
            entity.Property(e => e.LabAnalysis).HasMaxLength(255);
            entity.Property(e => e.SampleRetrieval).HasMaxLength(255);
            entity.Property(e => e.SampleSpecify).HasMaxLength(255);
            entity.Property(e => e.SampleType).HasMaxLength(255);
            entity.Property(e => e.Status).HasDefaultValue(0, "DF_Lab_Request_Status");
            entity.Property(e => e.SubInfo2).HasMaxLength(255);
            entity.Property(e => e.SubInfo4).HasMaxLength(255);
        });

        modelBuilder.Entity<LabRequestLabWork>(entity =>
        {
            entity.HasKey(e => e.LabWorkId);

            entity.ToTable("Lab_RequestLabWork");

            entity.HasIndex(e => e.Analysis, "Index_Analysis");

            entity.HasIndex(e => e.AssignedTo, "Index_AssignedTo");

            entity.HasIndex(e => e.TestId, "Index_TestID");

            entity.Property(e => e.LabWorkId).HasColumnName("LabWorkID");
            entity.Property(e => e.AnalysisSpecify).HasMaxLength(255);
            entity.Property(e => e.Result).HasMaxLength(50);
            entity.Property(e => e.TestId).HasColumnName("TestID");
            entity.Property(e => e.Unit).HasMaxLength(50);

            entity.HasOne(d => d.AnalysisNavigation).WithMany(p => p.LabRequestLabWorks)
                .HasForeignKey(d => d.Analysis)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("Foreign_AnalysisList_Analysis");

            entity.HasOne(d => d.Test).WithMany(p => p.LabRequestLabWorks)
                .HasForeignKey(d => d.TestId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("Foreign_RequestList_TestID");
        });

        modelBuilder.Entity<LabRequestList>(entity =>
        {
            entity.HasKey(e => e.TestId).HasName("PrimaryKey_TestID");

            entity.ToTable("Lab_RequestList");

            entity.HasIndex(e => e.LaboratoryNumber, "Index_LaboratoryNumber")
                .IsUnique()
                .HasFilter("([LaboratoryNumber] IS NOT NULL)");

            entity.HasIndex(e => e.RequestId, "Index_RequestID");

            entity.HasIndex(e => e.Status, "Index_Status");

            entity.Property(e => e.TestId).HasColumnName("TestID");
            entity.Property(e => e.Analysis).HasMaxLength(255);
            entity.Property(e => e.AnalysisOthers).HasMaxLength(255);
            entity.Property(e => e.CustomerSampleCode).HasMaxLength(255);
            entity.Property(e => e.LaboratoryNumber).HasMaxLength(255);
            entity.Property(e => e.PlaceCode)
                .HasMaxLength(50)
                .IsUnicode(false);
            entity.Property(e => e.PlaceCollected).HasMaxLength(255);
            entity.Property(e => e.RequestId).HasColumnName("RequestID");
            entity.Property(e => e.Sample).HasMaxLength(255);
            entity.Property(e => e.Status).HasDefaultValue(0, "DF_Lab_RequestList_Status");

            entity.HasOne(d => d.Request).WithMany(p => p.LabRequestLists)
                .HasForeignKey(d => d.RequestId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("Foreign_Requests_RequestID");
        });

        modelBuilder.Entity<SysAuditLog>(entity =>
        {
            entity.HasKey(e => e.LogId);

            entity.ToTable("Sys_AuditLogs");

            entity.HasIndex(e => e.CreatedAt, "Index_CreatedAt");

            entity.HasIndex(e => e.RequestId, "Index_RequestID");

            entity.HasIndex(e => e.UserId, "Index_UserID");

            entity.Property(e => e.LogId).HasColumnName("LogID");
            entity.Property(e => e.Action).HasMaxLength(150);
            entity.Property(e => e.LabNumber).HasMaxLength(50);
            entity.Property(e => e.LabWorkId).HasColumnName("LabWorkID");
            entity.Property(e => e.Module).HasMaxLength(150);
            entity.Property(e => e.RequestId).HasColumnName("RequestID");
            entity.Property(e => e.TestId).HasColumnName("TestID");
            entity.Property(e => e.UserId).HasColumnName("UserID");
            entity.Property(e => e.UserName).HasMaxLength(150);
        });

        modelBuilder.Entity<SysAuditLogsChange>(entity =>
        {
            entity.HasKey(e => e.ChangeId);

            entity.ToTable("Sys_AuditLogsChanges");

            entity.HasIndex(e => e.LogId, "Index_LogID");

            entity.Property(e => e.ChangeId).HasColumnName("ChangeID");
            entity.Property(e => e.FieldName).HasMaxLength(100);
            entity.Property(e => e.LogId).HasColumnName("LogID");

            entity.HasOne(d => d.Log).WithMany(p => p.SysAuditLogsChanges)
                .HasForeignKey(d => d.LogId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("Foreign_AuditLogs_LogID");
        });

        OnModelCreatingPartial(modelBuilder);
    }

    partial void OnModelCreatingPartial(ModelBuilder modelBuilder);
}
