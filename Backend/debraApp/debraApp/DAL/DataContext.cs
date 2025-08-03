using debraApp.Models;
using Microsoft.EntityFrameworkCore;

namespace debraApp.DAL
{
    public class DataContext : DbContext
    {
        public DataContext(DbContextOptions<DataContext> options) : base(options)
        {
        }

        public DbSet<Partner> Partners { get; set; }
        public DbSet<Event> Events { get; set; }
        public DbSet<Ticket> Tickets { get; set; }
        public DbSet<Sale> Sales { get; set; }
        public DbSet<Customer> Customers { get; set; }
        public DbSet<User> Users { get; set; }
        public DbSet<Commission> Commissions { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            // Event - Partner relationship
            modelBuilder.Entity<Event>()
                .HasOne(e => e.Partner)
                .WithMany(p => p.Events)
                .HasForeignKey(e => e.PartnerID);

            // Event - Commission relationship
            modelBuilder.Entity<Event>()
                .HasOne(e => e.Commission)
                .WithOne(c => c.Event)
                .HasForeignKey<Commission>(c => c.EventID);

            // Event - Ticket relationship
            modelBuilder.Entity<Ticket>()
                .HasOne(t => t.Event)
                .WithMany(e => e.Tickets)
                .HasForeignKey(t => t.EventID);

            // Ticket - Sale relationship
            modelBuilder.Entity<Sale>()
                .HasOne(s => s.Ticket)
                .WithMany(t => t.Sales)
                .HasForeignKey(s => s.TicketID);

            // Sale - Customer relationship
            modelBuilder.Entity<Sale>()
                .HasOne(s => s.Customer)
                .WithMany(c => c.Sales)
                .HasForeignKey(s => s.CustomerID);

            // Configure decimal properties
            modelBuilder.Entity<Commission>()
                .Property(e => e.CommissionRate)
                .HasPrecision(18, 2); // Example precision

            modelBuilder.Entity<Commission>()
                .Property(c => c.TotalSales)
                .HasPrecision(18, 2); // Example precision



            modelBuilder.Entity<Ticket>()
                .Property(t => t.Price)
                .HasPrecision(18, 2); // Example precision
        }
    }
}
